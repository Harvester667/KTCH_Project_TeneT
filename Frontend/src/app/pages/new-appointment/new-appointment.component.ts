import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../auth.service';
import { Router } from '@angular/router';
import { AppointmentService } from '../../models/appointment-service.model';
import { HttpClient, HttpHeaders } from '@angular/common/http';

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
    const storedUser = localStorage.getItem('user');
    const user = storedUser ? JSON.parse(storedUser) : null;
  
    let headers = new HttpHeaders();
  
    if (user && (user.admin === 1 || user.admin === 2)) {
      const token = localStorage.getItem('token');
      if (token) {
        headers = headers.set('Authorization', `Bearer ${token}`);
      }
    }
  
    this.http.get<any>('http://localhost:8000/api/getusers', { headers }).subscribe({
      next: (response: any) => {
        console.log('Lekért felhasználók:', response);
        if (response && Array.isArray(response.data)) {
          this.employees = response.data.filter((user: any) => user.admin === 1);
        } else {
          console.error('Nem megfelelő formátumú válasz:', response);
        }
      },
      error: (error) => {
        console.error('Hiba a fodrászok lekérésekor', error);
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
  
    let userId0 = this.user.id; // alapértelmezetten a bejelentkezett felhasználó
    let useForceBooking = false;
  
    // Superadmin más nevében is tud foglalni
    if (this.user.admin === 2 && email) {
      const selectedUser = this.users.find(u => u.email === email);
      if (!selectedUser) {
        alert('A kiválasztott e-mail cím nem található!');
        return;
      }
      userId0 = selectedUser.id;
      useForceBooking = true;
    }
  
    const bookingDateTime = `${appointmentDate} ${appointmentTime}:00`;
  
    const bookingData = {
      user_id_0: Number(userId0),
      user_id_1: Number(stylist),
      service_id: Number(service),
      booking_time: bookingDateTime,
      active: 1
    };
  
    console.log('📦 BookingData elküldésre:', bookingData);
  
    if (useForceBooking) {
      // Admin más nevében foglal -> forceBooking hívás
      this.authService.forceBookAppointment(bookingData).subscribe({
        next: (res: any) => {
          console.log('✅ ForceBooking sikeres:', res);
          this.showSuccessModal();
          this.updateAvailableTimes();
        },
        error: (error) => {
          console.error('❌ ForceBooking hiba:', error);
          this.showErrorModal();
        }
      });
    } else {
      // Saját nevében foglal -> normál booking
      this.authService.bookAppointment(bookingData).subscribe({
        next: (res: any) => {
          console.log('✅ Foglalás sikeres:', res);
          this.showSuccessModal();
          this.updateAvailableTimes();
        },
        error: (error) => {
          console.error('❌ Hiba a foglalás mentésekor:', error);
          this.showErrorModal();
        }
      });
    }
  }

  showSuccessModal() {
    const modalElement = document.getElementById('bookingSuccessModal');
    if (modalElement) {
      new bootstrap.Modal(modalElement).show();
    }
  }
  
  showErrorModal() {
    const modalElement = document.getElementById('bookingErrorModal');
    if (modalElement) {
      new bootstrap.Modal(modalElement).show();
    }
  }
  
  
  

  updateAvailableTimes(): void {
    if (!this.appointmentObj.stylist || !this.appointmentObj.appointmentDate) return;
  
    const date = new Date(this.appointmentObj.appointmentDate);
    const day = date.getDay(); // 0 = vasárnap, 6 = szombat
  
    // Ünnepnapok 2025-re
    const holidays = [
      '2025-01-01', '2025-03-15', '2025-04-18', '2025-04-21',
      '2025-05-01', '2025-06-09', '2025-08-20', '2025-10-23',
      '2025-11-01', '2025-12-24', '2025-12-25', '2025-12-26'
    ];
  
    const selectedDate = this.appointmentObj.appointmentDate;
  
    // Ha hétvége vagy ünnepnap, nem engedélyezett
    if (day === 0 || day === 6 || holidays.includes(selectedDate)) {
      this.availableTimes = [];
      console.warn('Ez a nap nem elérhető: hétvége vagy ünnepnap');
      return;
    }
  
    const stylistId = this.appointmentObj.stylist;
    const token = localStorage.getItem('token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
  
    this.http.get<any>(`http://localhost:8000/api/whoisbooking?user_id_1=${stylistId}`, { headers }).subscribe({
      next: (response) => {
        console.log('Lekért foglalások:', response);
  
        const bookings = response.data || [];
  
        const bookedTimes = bookings
          .filter((b: any) => b.booking_time.startsWith(selectedDate))
          .map((b: any) => {
            const timePart = b.booking_time.split(' ')[1].slice(0, 5); // pl: "09:00"
            return timePart;
          });
  
        console.log('Foglalások erre a napra:', bookedTimes);
  
        const allTimes = this.generateTimes();
        this.availableTimes = allTimes.filter(time => !bookedTimes.includes(time));
        console.log('Szabad időpontok:', this.availableTimes);
      },
      error: (error) => {
        console.error('Hiba a foglalt idők lekérdezésekor:', error);
        this.availableTimes = this.generateTimes();
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
