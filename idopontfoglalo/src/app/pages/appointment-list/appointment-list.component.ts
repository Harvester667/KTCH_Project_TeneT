import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../auth.service';  // AuthService importálása

@Component({
  selector: 'app-appointment-list',
  templateUrl: './appointment-list.component.html',
  styleUrls: ['./appointment-list.component.css']
})
export class AppointmentListComponent implements OnInit {
  bookings: any[] = [];  // Foglalások tárolása

  constructor(private authService: AuthService) { }

  ngOnInit(): void {
    this.loadBookings();  // Foglalások betöltése a komponens inicializálásakor
  }

  loadBookings(): void {
    this.authService.getBookings().subscribe(
      (response) => {
        if (response.success) {
          this.bookings = response.data;  // A válasz adatainak eltárolása
        }
      },
      (error) => {
        console.error('Hiba történt a foglalások betöltésekor', error);
      }
    );
  }
}
