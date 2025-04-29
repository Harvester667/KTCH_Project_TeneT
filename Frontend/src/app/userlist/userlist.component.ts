import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
declare var bootstrap: any;
import { forkJoin } from 'rxjs';

@Component({
  selector: 'app-userlist',
  templateUrl: './userlist.component.html',
  styleUrls: ['./userlist.component.css']
})
export class UserlistComponent implements OnInit {
  users: any[] = [];
  selectedUser: any;
  selectedAction: string = '';
  modalTitle: string = '';
  modalMessage: string = '';
  successModalTitle: string = '';
  successModalMessage: string = '';

  constructor(private authService: AuthService) {}

  ngOnInit(): void {
    this.loadUsers();
  }

  loadUsers() {
    this.authService.getUsers().subscribe(
      (data: any) => {
        this.users = data;
      },
      error => {
        console.error('Hiba a felhasználók betöltésekor', error);
      }
    );
  }

  openModal(action: string, user: any) {
    this.selectedUser = user;
    this.selectedAction = action;
    if (action === 'setAdmin') {
      this.modalTitle = 'Feljogosítás megerősítése';
      this.modalMessage = `Biztosan feljogosítod ${user.name} felhasználót adminnak?`;
    } else if (action === 'demotivate') {
      this.modalTitle = 'Lefokozás megerősítése';
      this.modalMessage = `Biztosan lefokozod ${user.name} felhasználót?`;
    }
    const actionModal = new bootstrap.Modal(document.getElementById('actionModal'));
    actionModal.show();
  }

  closeModal() {
    const actionModal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
    if (actionModal) {
      actionModal.hide();
    }
  }

  closeSuccessModal() {
    const successModal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
    if (successModal) {
      successModal.hide();
    }
  }

  showSuccessModal(title: string, message: string) {
    this.successModalTitle = title;
    this.successModalMessage = message;
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
  }

  confirmAction() {
    if (this.selectedAction === 'setAdmin') {
      forkJoin([
        this.authService.setAdmin(this.selectedUser.id),
        this.authService.employee(this.selectedUser.id)
      ]).subscribe(
        ([adminResponse, employeeResponse]) => {
          this.closeModal();
          this.showSuccessModal('Siker!', 'A felhasználó admin jogosultságot kapott.');
          this.loadUsers();
        },
        error => {
          console.error('Hiba a feljogosítás során', error);
        }
      );
    } else if (this.selectedAction === 'demotivate') {
      forkJoin([
        this.authService.demotivate(this.selectedUser.id),
        this.authService.customer(this.selectedUser.id)
      ]).subscribe(
        ([demotivateResponse, customerResponse]) => {
          this.closeModal();
          this.showSuccessModal('Siker!', 'A felhasználó jogosultsága visszavonva.');
          this.loadUsers();
        },
        error => {
          console.error('Hiba a lefokozás során', error);
        }
      );
    }
  }

  getAdminRole(admin: number): string {
    return admin === 1 ? 'Admin' : 'Felhasználó';
  }
}
