import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  showLoginModal = false;
  showRegisterModal = false;

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
}
