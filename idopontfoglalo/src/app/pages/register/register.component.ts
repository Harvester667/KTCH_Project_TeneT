import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';


@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  user = {
    fullname: '',
    gender: '',
    username: '',
    email: '',
    password: ''
  };

  constructor(private authService: AuthService) {}

  registerUser() {
    this.authService.register(this.user).subscribe(response => {
      console.log('Sikeres regisztráció:', response);
    }, error => {
      console.error('Hiba történt:', error);
    });
  }
}
