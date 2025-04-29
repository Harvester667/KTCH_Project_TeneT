import { Component } from '@angular/core';
import { AuthService } from '../auth.service'; // AuthService importálása
import { Router } from '@angular/router';

declare var bootstrap: any;

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {

  registrationObj = {
    name: '',
    email: '',
    password: '',
    confirm_password: ''
  };

  constructor(private authService: AuthService, private router: Router) {}

  onRegister() {
    const { name, email, password, confirm_password } = this.registrationObj;
  
    if (!name || !email || !password || !confirm_password || password !== confirm_password) {
      const errorModal = new bootstrap.Modal(document.getElementById('registerErrorModal'));
      errorModal.show();
      return;
    }
  
    this.authService.register(this.registrationObj).subscribe(
      response => {
        console.log('Regisztráció sikeres:', response);
        const successModal = new bootstrap.Modal(document.getElementById('registerSuccessModal'));
        successModal.show();
  
        // Átirányítás 2 másodperc múlva
        setTimeout(() => {
          this.router.navigate(['/home']);
        }, 2000);
      },
      error => {
        console.error('Hiba a regisztráció során:', error);
        const errorModal = new bootstrap.Modal(document.getElementById('registerErrorModal'));
        errorModal.show();
      }
    );
  }
  
}  
