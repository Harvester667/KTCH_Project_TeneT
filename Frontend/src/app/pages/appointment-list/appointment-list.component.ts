import { Component, OnInit, ViewChild } from '@angular/core';
import { AuthService } from '../../auth.service';
import { Router } from '@angular/router';

declare var bootstrap: any; // Bootstrap deklarálása

@Component({
  selector: 'app-appointment-list',
  templateUrl: './appointment-list.component.html',
  styleUrls: ['./appointment-list.component.css']
})
export class AppointmentListComponent implements OnInit {
  futureBookings: any[] = [];  // Jövőbeni foglalások
  pastBookings: any[] = [];    // Múltbeli foglalások
  allBookings: any[] = [];     // Összes foglalás
  user: any;                  // Bejelentkezett felhasználó
  currentDate: string = new Date().toISOString().split('T')[0]; // Mai dátum (ISO formátum)
  filter: string = 'all';     // Aktuális szűrő: 'all' vagy 'today'
  bookingToDeleteId: number | null = null;  // A törléshez választott foglalás ID-ja

  // Modalok hozzáadása
  @ViewChild('deleteConfirmModal', { static: false }) deleteConfirmModal: any;
  @ViewChild('deleteSuccessModal', { static: false }) deleteSuccessModal: any;

  constructor(private authService: AuthService) {}

  ngOnInit(): void {
    const userData = JSON.parse(localStorage.getItem('user') || '{}');
    this.user = {
      admin: Number(userData.admin) || 0,
      ...userData
    };
    this.loadBookings(this.filter);
  }

  loadBookings(filter: string): void {
    this.authService.getBookings().subscribe(
      (response) => {
        if (response.success) {
          const now = new Date();
  
          const sorted = response.data.sort((a: any, b: any) => {
            const dateA = new Date(a.appointment_date + 'T' + a.appointment_time);
            const dateB = new Date(b.appointment_date + 'T' + b.appointment_time);
            return dateB.getTime() - dateA.getTime();
          });
  
          this.futureBookings = sorted.filter((b: any) => {
            const bookingDate = new Date(b.appointment_date + 'T' + b.appointment_time);
            return bookingDate >= now;
          });
  
          this.pastBookings = sorted.filter((b: any) => {
            const bookingDate = new Date(b.appointment_date + 'T' + b.appointment_time);
            return bookingDate < now;
          });
  
          if (filter === 'today') {
            // Csak a mai napra eső foglalások
            const todayBookings = sorted.filter((b: any) => {
              const bookingDate = new Date(b.appointment_date + 'T' + b.appointment_time);
              return bookingDate.getFullYear() === now.getFullYear() &&
                     bookingDate.getMonth() === now.getMonth() &&
                     bookingDate.getDate() === now.getDate();
            });
  
            this.allBookings = todayBookings;
          } else {
            // Alapértelmezésben csak a jövőbeni foglalásokat mutassuk kártyaként
            this.allBookings = this.futureBookings;
          }
  
          this.filter = filter;
        }
      },
      (error) => {
        console.error('Hiba történt a foglalások betöltésekor', error);
      }
    );
  }

  isLateBooking(booking: any): boolean {
    const now = new Date();
    const bookingDate = new Date(booking.appointment_date + 'T' + booking.appointment_time);
    return bookingDate < now && bookingDate.getFullYear() === now.getFullYear() &&
           bookingDate.getMonth() === now.getMonth() && bookingDate.getDate() === now.getDate();
  }

  isWithinTwoDays(bookingDate: string): boolean {
    const today = new Date();
    const bookingDateObj = new Date(bookingDate);
  
    // Normalizáljuk a dátumokat (idő nélküli összehasonlításhoz)
    today.setHours(0, 0, 0, 0);
    bookingDateObj.setHours(0, 0, 0, 0);
  
    const timeDiff = bookingDateObj.getTime() - today.getTime();
    const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
  
    return daysDiff >= 0 && daysDiff <= 2;
  }
  deleteConfirmModalInstance: any;

  deleteBooking(bookingId: number): void {
    this.bookingToDeleteId = bookingId;
    this.deleteConfirmModalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmModal')!);
    this.deleteConfirmModalInstance.show();
  }

  confirmDelete(): void {
    if (this.bookingToDeleteId !== null && this.deleteConfirmModalInstance) {
      this.deleteConfirmModalInstance.hide();
  
      this.authService.delete(`delbooking/${this.bookingToDeleteId}`).subscribe(
        (response) => {
          const deleteSuccessModalInstance = new bootstrap.Modal(document.getElementById('deleteSuccessModal')!);
          deleteSuccessModalInstance.show();
  
          this.loadBookings(this.filter);
        },
        (error) => {
          console.error('Hiba történt a foglalás törlésénél', error);
        }
      );
    }
  }
}
