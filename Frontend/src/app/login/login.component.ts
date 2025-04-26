import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';

declare var bootstrap: any;


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  loginObj = { email: '', password: '' };

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onLogin() {
    // Ellenőrizzük, hogy valamelyik mező üres-e
    if (!this.loginObj.email || !this.loginObj.password) {
      const errorModalElement = document.getElementById('errorModal');
      const errorModal = new bootstrap.Modal(errorModalElement);
      errorModal.show();
      return; // Megállítjuk a további futást
    }
  
    this.authService.login(this.loginObj).subscribe(
      (response: any) => {
        console.log('Sikeres bejelentkezés', response);
  
        const modalElement = document.getElementById('successModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
  
        this.router.navigate(['/home']);
      },
      (error) => {
        console.error('Hiba a bejelentkezés során', error);
  
        const errorModalElement = document.getElementById('errorModal');
        const errorModal = new bootstrap.Modal(errorModalElement);
        errorModal.show();
      }
    );
  }
  
}
