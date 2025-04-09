import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})


export class HomeComponent implements OnInit {
  isLoggedIn = false;
  userName = '';

  constructor(private authService: AuthService) {}

  employees: any[] = [];
  user: any = null;



  ngOnInit(): void {
    this.loadUserData();
    this.loadEmployees();
    // Feliratkozás a user$ observable-ra, így mindig naprakész adatokat kapunk
    this.authService.user$.subscribe(user => {
      if (user) {
        this.userName = user.email;  // Az email cím a felhasználó objektumból
        this.isLoggedIn = true;
      } else {
        this.isLoggedIn = false;
      }
    });
  }

  logout() {
    this.authService.logout();
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

  

}
