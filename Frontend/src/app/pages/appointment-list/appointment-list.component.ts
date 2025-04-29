import { Component, OnInit, ViewChild } from '@angular/core';
import { AuthService } from '../../auth.service';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';

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
  users: any[] = [];
  services: any[] = [];

  // Modalok hozzáadása
  @ViewChild('deleteConfirmModal', { static: false }) deleteConfirmModal: any;
  @ViewChild('deleteSuccessModal', { static: false }) deleteSuccessModal: any;

  constructor(private authService: AuthService, private router: Router, private http: HttpClient) {}

  ngOnInit(): void {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  
    this.loadCurrentUser();
    this.loadUsers();
    this.loadServices();
    this.loadBookings('all');
  }

  loadServices() {
    this.authService.getServices().subscribe({
      next: (res: any) => {
        if (res && res.data) {
          this.services = res.data;
        }
      },
      error: (err) => {
        console.error('Szolgáltatások lekérése hiba:', err);
      }
    });
  }

  loadCurrentUser() {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  }

  loadUsers() {
    this.authService.getUsers().subscribe({
      next: (res: any) => {
        this.users = res;
      },
      error: (err) => {
        console.error('Felhasználók lekérése hiba:', err);
      }
    });
  }

  loadBookings(filter: string) {
    this.filter = filter;
  
    this.authService.getBookings().subscribe({
      next: (res: any) => {
        let bookings = res.data || [];
  
        // --- Hozzákötjük a nevet és szolgáltatást ID alapján ---
        bookings = bookings.map((booking: any) => {
          const user0 = this.users.find(u => u.id === booking.user_id_0);
          const user1 = this.users.find(u => u.id === booking.user_id_1);
          const service = this.services.find(s => s.id === booking.service_id);
  
          return {
            ...booking,
            customerName: user0 ? user0.name : 'Ismeretlen vendég',
            employeeName: user1 ? user1.name : 'Ismeretlen fodrász',
            serviceName: service ? service.service : 'N/A'
          };
        });

        bookings.sort((a: any, b: any) => new Date(a.booking_time).getTime() - new Date(b.booking_time).getTime());
  
        // --- Itt jön az új szűrés: ---
        if (this.user?.admin !== 2) {
          bookings = bookings.filter((b: any) =>
            b.user_id_0 === this.user.id || b.user_id_1 === this.user.id
          );
        }
  
        // --- Mai nap dátuma ---
        const todayStr = new Date().toISOString().split('T')[0];
        const todayDate = new Date(todayStr);
  
        if (filter === 'today') {
          this.allBookings = bookings.filter((b: any) => b.booking_time.startsWith(todayStr));
          this.pastBookings = [];
        } else {
          this.allBookings = bookings.filter((b: any) => {
            const bookingDate = new Date(b.booking_time);
            return bookingDate >= todayDate;
          });
          this.pastBookings = bookings.filter((b: any) => {
            const bookingDate = new Date(b.booking_time);
            return bookingDate < todayDate;
          });
        }
      },
      error: (err) => {
        console.error('Foglalások lekérése hiba:', err);
      }
    });
  }

  canDeleteBooking(booking: any): boolean {
    if (this.user.admin === 2 || this.user.admin === 1) {
      return true; // Admin mindig törölhet
    }
  
    if (this.user.id === booking.user_id_0 || this.user.id === booking.user_id_1) {
      const bookingDate = new Date(booking.booking_time);
      const today = new Date();
  
      // KINULLÁZZUK az időt mindkét dátumnál
      bookingDate.setHours(0, 0, 0, 0);
      today.setHours(0, 0, 0, 0);
  
      const diffInMs = bookingDate.getTime() - today.getTime();
      const diffInDays = diffInMs / (1000 * 60 * 60 * 24);
  
  
      return diffInDays >= 3;
    }
  
    return false;
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
    this.selectedBookingId = bookingId;
    const modalElement = document.getElementById('deleteConfirmModal');
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show(); // 👈 Itt CSAK megnyitod a modalt
    }
  }
  

  selectedBookingId: number | null = null;
  
  confirmDelete(): void {
    if (!this.selectedBookingId) return;
  
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('Nincs token!');
      return;
    }
  
    const headers = new HttpHeaders()
      .set('Authorization', `Bearer ${token}`);
  
    this.http.delete(`http://localhost:8000/api/delbooking/${this.selectedBookingId}`, { headers }).subscribe({
      next: () => {
        console.log('Foglalás törölve');
        
        // Bezárjuk a modalt
        const confirmModal = document.getElementById('deleteConfirmModal');
        if (confirmModal) {
          const modal = bootstrap.Modal.getInstance(confirmModal);
          modal?.hide();
        }
  
        // Siker modal megnyitása
        const successModal = document.getElementById('deleteSuccessModal');
        if (successModal) {
          new bootstrap.Modal(successModal).show();
        }
  
        this.loadBookings(this.filter); // újratöltés
      },
      error: (error) => {
        console.error('Hiba törlés közben:', error);
      }
    });
  }
  
  
  
  
  
}
