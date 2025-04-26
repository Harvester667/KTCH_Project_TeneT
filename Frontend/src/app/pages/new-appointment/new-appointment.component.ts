import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../auth.service';
import { Router } from '@angular/router';
import { AppointmentService } from '../../models/appointment-service.model';
import { HttpClient } from '@angular/common/http';

declare var bootstrap: any; // Importáljuk az interfészt

@Component({
  selector: 'app-new-appointment',
  templateUrl: './new-appointment.component.html',
  styleUrls: ['./new-appointment.component.css']
})
export class NewAppointmentComponent implements OnInit {
  appointmentObj: any = {
    service: '',
    appointmentDate: '',
    appointmentTime: '',
    stylist: ''
  };

  minDate: string = '';
  availableTimes: string[] = [];
  availableServices: AppointmentService[] = [];
  user: any = null;

  employees: any[] = [];
  users: any[] = [];

  constructor(private http: HttpClient, private authService: AuthService, private router: Router) {}

  ngOnInit() {
    this.setMinDate();
    this.loadUserData();
    this.loadServices();
    this.loadEmployees();
  }

  loadEmployees() {
    this.authService.getEmployees().subscribe({
      next: (response) => {
        console.log("Dolgozók API válasza:", response);
        if (response.success) {
          this.employees = response.data;
        } else {
          console.error("Hiba a dolgozók lekérésekor:", response);
        }
      },
      error: (err) => {
        console.error("Hálózati vagy API hiba:", err);
      }
    });
  }
  
  loadUserData() {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  
    // Feliratkozás a felhasználó változásaira
    this.authService.user$.subscribe(user => {
      if (user) {
        this.user = user;
        if (this.user.admin === 2) {
          this.loadAllUsers(); // Csak admin jogosultság esetén töltjük be az összes felhasználót
        }
      }
    });
  
    // Ha az oldal újratöltődik, lekérdezzük a felhasználót a backendből
    this.authService.getUser().subscribe(user => {
      this.user = user;
      if (this.user?.admin === 2) {
        this.loadAllUsers(); // Admin esetén betöltjük az összes felhasználót
      }
    });
  }

  loadServices() {
    this.authService.getServices().subscribe(
      (response: any) => {
        if (response && response.data && Array.isArray(response.data)) {
          this.availableServices = response.data.map((service: any) => ({
            service: service.service,
            value: service.id
          }));
        } else {
          console.error('Szolgáltatások nem megfelelő formátumban:', response);
        }
      },
      error => {
        console.error('Szolgáltatások betöltése hiba: ', error);
      }
    );
  }

  setMinDate() {
    const today = new Date();
    this.minDate = today.toISOString().split('T')[0];
  }



  generateTimeSlots(startHour: number, endHour: number, stepMinutes: number): string[] {
    let times: string[] = [];
    for (let hour = startHour; hour < endHour; hour++) {
      for (let min = 0; min < 60; min += stepMinutes) {
        times.push(`${this.padZero(hour)}:${this.padZero(min)}`);
      }
    }
    return times;
  }

  padZero(num: number): string {
    return num < 10 ? `0${num}` : `${num}`;
  }

  onStylistChange() {
    this.updateAvailableTimes();
  }

  onServiceChange() {
    this.updateAvailableTimes();
  }

  onSaveAppointment() {
    if (!this.user) {
      alert('Be kell jelentkezni a foglaláshoz!');
      return;
    }
  
    const { appointmentDate, appointmentTime, service, stylist, email } = this.appointmentObj;
  
    if (!appointmentDate || !appointmentTime || !service || !stylist) {
      alert('Kérlek, töltsd ki az összes mezőt!');
      return;
    }
  
    let userId = this.user.id;
  
    // Superadmin e-mail alapján választ másik felhasználót
    if (this.user.admin === 2 && email) {
      const selectedUser = this.users.find(user => user.email === email);
      if (!selectedUser) {
        alert('A kiválasztott e-mail cím nem található!');
        return;
      }
      userId = selectedUser.id;
    }
  
    // Booking adatcsomag
    const bookingData = {
      user_id: userId,
      employee_id: stylist,
      service_id: service,
      appointment_date: appointmentDate,
      appointment_time: appointmentTime
    };
  
    console.log('📦 BookingData elküldésre:', bookingData);
  
    this.authService.bookAppointment(bookingData).subscribe({
      next: (res: any) => {
        console.log('✅ Foglalás sikeres:', res);
  
        const modalElement = document.getElementById('bookingSuccessModal');
        if (modalElement) {
          new bootstrap.Modal(modalElement).show();
        }
  
        this.updateAvailableTimes();
      },
      error: (error) => {
        console.error('❌ Hiba a foglalás mentésekor:', error);
  
        const errorModalElement = document.getElementById('bookingErrorModal');
        if (errorModalElement) {
          new bootstrap.Modal(errorModalElement).show();
        }
      }
    });
  }
  
  
  
  
  
  

  updateAvailableTimes(): void {
    if (!this.appointmentObj.stylist || !this.appointmentObj.appointmentDate) return;
  
    const selectedDate = this.appointmentObj.appointmentDate;
    const stylistId = this.appointmentObj.stylist;
  
    const date = new Date(selectedDate);
    const day = date.getDay(); // 0 = vasárnap, 6 = szombat
  
    // Magyar ünnepnapok (2025)
    const holidays = [
      '2025-01-01', // Újév
      '2025-03-15', // Nemzeti ünnep
      '2025-04-18', // Nagypéntek 
      '2025-04-21', // Húsvét hétfő
      '2025-05-01', // Munka ünnepe
      '2025-06-09', // Pünkösd hétfő
      '2025-08-20', // Alkotmány napja
      '2025-10-23', // 1956-os forradalom
      '2025-11-01', // Mindenszentek
      '2025-12-24', // Szenteste
      '2025-12-25', // Karácsony
      '2025-12-26'  // Karácsony másnapja
    ];
  
    // Ha hétvége vagy ünnepnap, nem elérhető
    if (day === 0 || day === 6 || holidays.includes(selectedDate)) {
      this.availableTimes = [];
      console.warn('Ez a nap nem elérhető: hétvége vagy ünnepnap');
      return;
    }
  
    console.log('Lekérés indul:', stylistId, selectedDate);
  
    this.http.get<string[]>(`http://localhost:8000/api/booked-times/${stylistId}/${selectedDate}`).subscribe({
      next: (bookedTimes: string[]) => {
        console.log('Foglalások a szerverről:', bookedTimes);
        this.availableTimes = this.generateTimes().filter(time =>
          !bookedTimes.some(booked => booked.startsWith(time))
        );
        console.log('Szabad időpontok:', this.availableTimes);
      },
      error: (err) => {
        console.error('Hiba az API hívásban:', err);
        this.availableTimes = [];
      }
    });
  }
  
  

  generateTimes(): string[] {
    const times: string[] = [];
    let start = 9;
    let end = 17;
  
    for (let hour = start; hour < end; hour++) {
      times.push(`${this.pad(hour)}:00`);
      times.push(`${this.pad(hour)}:30`);
    }
  
    return times;
  }

  pad(num: number): string {
    return num < 10 ? '0' + num : num.toString();
  }



  loadAllUsers() {
    this.authService.getUsers().subscribe({
      next: (response) => {
        console.log("Lekért felhasználók:", response);
        this.users = response; // ✅ A felhasználói lista betöltése
      },
      error: (error) => {
        console.error("Hiba a felhasználók lekérésekor", error);
      }
    });
  }
}
