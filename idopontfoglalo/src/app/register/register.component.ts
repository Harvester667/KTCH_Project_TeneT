import { Component } from '@angular/core';
import { AuthService } from '../auth.service'; // AuthService importálása
import { Router } from '@angular/router';

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
    this.authService.register(this.registrationObj).subscribe(
      response => {
        console.log('Regisztráció sikeres:', response);
        alert('Sikeres regisztráció!');
        this.router.navigate(['/home']); // Átirányítás a főoldalra
      },
      error => {
        console.log('Hiba a regisztráció során:', error);
        alert('Hiba történt a regisztráció során.');
      }
    );
  }
}
