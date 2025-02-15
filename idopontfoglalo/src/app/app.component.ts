import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http'; // Backend kapcsolat
import { FormBuilder, FormGroup, Validators } from '@angular/forms'; // Reactive forms

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  showLoginModal = false;
  showRegisterModal = false;
  registerForm: FormGroup; // Regisztrációs űrlap

  constructor(private fb: FormBuilder, private http: HttpClient) {
    // FormGroup létrehozása és validátorok hozzáadása
    this.registerForm = this.fb.group({
      fullName: ['', Validators.required],
      gender: ['', Validators.required],
      username: ['', [Validators.required, Validators.minLength(3)]],
      registerEmail: ['', [Validators.required, Validators.email]],
      registerPassword: ['', [Validators.required, Validators.minLength(6)]],
      confirmPassword: ['', Validators.required]
    });
  }

  openLoginModal() {
    this.showLoginModal = true;
  }

  openRegisterModal() {
    this.showRegisterModal = true;
  }

  closeModal(modalType: string) {
    if (modalType === 'login') {
      this.showLoginModal = false;
    } else if (modalType === 'register') {
      this.showRegisterModal = false;
    }
  }

  // Regisztráció beküldése
  onSubmit() {
    if (this.registerForm.valid) {
      const formData = this.registerForm.value;
      // Például: Backend hívás a regisztrációs adatküldéshez
      this.http.post('http://your-backend-api-url/register', formData).subscribe(
        response => {
          console.log('Regisztráció sikeres:', response);
          // Itt további lépéseket tehetsz, pl. modal bezárása, hiba kezelése, stb.
        },
        error => {
          console.error('Regisztráció hiba:', error);
        }
      );
    } else {
      console.log('Form invalid');
    }
  }
}
