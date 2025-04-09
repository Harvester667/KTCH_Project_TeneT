import { Component } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  loginObj = {
    email: '',
    password: ''
  };

  constructor(private authService: AuthService, private router: Router) {}

  onLogin() {
    this.authService.login(this.loginObj).subscribe(
      (response: any) => {
        console.log('Sikeres bejelentkezés', response);
        alert('Sikeres bejelentkezés!');
        this.router.navigate(['/home']);
      },
      error => {
        console.error('Hiba a bejelentkezés során', error);
        alert('Hibás bejelentkezési adatok!');
      }
    );
  }
  
}

