import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../auth.service';
import { AppointmentService } from '../../models/appointment-service.model'; // Importáljuk az interfészt

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
  availableServices: AppointmentService[] = [];  // Az AppointmentService interfészt használjuk itt
  user: any = null;

  stylistImage: string = ''; // A kiválasztott fodrász képe
  stylists: { name: string, value: string, image: string }[] = [
    { name: 'Sándor', value: 'sandor', image: 'assets/img/sandor2.png' },
    { name: 'Hajni', value: 'hajni', image: 'assets/img/hajni2.png' }
  ];

  employees: any[] = [];

  constructor(private authService: AuthService) {}

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
      }
    });

    // Ha az oldal újratöltődik, lekérdezzük a felhasználót a backendből
    this.authService.getUser().subscribe(user => {
      this.user = user;
    });
  }

  loadServices() {
    this.authService.getServices().subscribe(
      (response: any) => {
        if (response && response.data && Array.isArray(response.data)) {
          this.availableServices = response.data.map((service: any) => ({
            name: service.name,
            value: service.value
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

    if (!this.appointmentObj.appointmentDate || !this.appointmentObj.appointmentTime || !this.appointmentObj.service || !this.appointmentObj.stylist) {
      alert('Kérlek, töltsd ki az összes mezőt!');
      return;
    }

    const bookingData = {
      user_id: this.user.id,
      employee_id: this.appointmentObj.stylist,
      service_id: this.appointmentObj.service,
      appointment_date: this.appointmentObj.appointmentDate,
      appointment_time: this.appointmentObj.appointmentTime
    };

    this.authService.bookAppointment(bookingData).subscribe(
      (res: any) => {
        alert('Foglalás sikeresen mentve!');
        this.updateAvailableTimes();
      },
      error => {
        alert('Hiba történt a foglalás során: ' + (error.error?.message || 'Ismeretlen hiba'));
      }
    );
  }

  updateAvailableTimes() {
    if (!this.appointmentObj.appointmentDate || !this.appointmentObj.stylist) return;
  
    const selectedDate = new Date(this.appointmentObj.appointmentDate);
    const day = selectedDate.getDay();
  
    if (day === 0 || day === 6) {
      this.availableTimes = [];
      return;
    }
  
    let allTimes = this.generateTimeSlots(9, 17, 30);
  
    this.authService.getBookedAppointments(this.appointmentObj.stylist, this.appointmentObj.appointmentDate)
      .subscribe((bookedTimes: string[]) => {
        this.availableTimes = allTimes.filter(time => !bookedTimes.includes(time));
        if (bookedTimes.includes(this.appointmentObj.appointmentTime)) {
          this.appointmentObj.appointmentTime = ''; 
        }
      }, error => {
        console.error("Hiba történt a foglalt időpontok lekérésekor:", error);
        this.availableTimes = allTimes;
      });
  }
}
