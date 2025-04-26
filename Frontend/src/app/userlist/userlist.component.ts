import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { switchMap } from 'rxjs/operators';

declare var bootstrap: any; // A Bootstrap modalokhoz szükséges

@Component({
  selector: 'app-userlist',
  templateUrl: './userlist.component.html',
  styleUrls: ['./userlist.component.css']
})
export class UserlistComponent implements OnInit {
  users: any[] = []; // A felhasználók listája
  selectedUser: any = null; // A kiválasztott felhasználó, akit módosítani fogunk
  actionType: string = ''; // A végrehajtandó művelet (feljogosítás vagy lefokozás)
  modalTitle: string = ''; // A modal címe
  modalMessage: string = ''; // A modal üzenete
  successModalTitle: string = ''; // A sikeres művelet modal címke
  successModalMessage: string = ''; // A sikeres művelet modal üzenete

  constructor(private authService: AuthService) {}

  ngOnInit(): void {
    this.loadUsers(); // Felhasználók betöltése
  }

  loadUsers(): void {
    this.authService.getUsers().subscribe(
      (response: any) => {
        this.users = response; // Felhasználók adatainak beállítása
      },
      (error) => {
        console.error('Hiba a felhasználók betöltésekor', error);
      }
    );
  }

  // Az admin szerepkör kiírása
  getAdminRole(role: number): string {
    switch (role) {
      case 0: return 'Vendég';
      case 1: return 'Fodrász';
      case 2: return 'Admin';
      default: return 'Ismeretlen';
    }
  }

  // Feljogosítási vagy Lefokozási modal megnyitása
  openModal(action: string, user: any): void {
    this.selectedUser = user; // Kiválasztjuk a felhasználót
    this.actionType = action; // Művelet típusa

    // Modal tartalmának beállítása
    if (action === 'setAdmin') {
      this.modalTitle = 'Feljogosítás';
      this.modalMessage = `Biztosan feljogosítja ezt a felhasználót: ${user.name}?`;
    } else if (action === 'demotivate') {
      this.modalTitle = 'Lefokozás';
      this.modalMessage = `Biztosan le szeretné fokozni ezt a felhasználót: ${user.name}?`;
    }

    // Megnyitjuk a kérdező modalt
    const modal = new bootstrap.Modal(document.getElementById('actionModal'));
    modal.show();
  }

  // Művelet megerősítése
  confirmAction(): void {
    if (this.actionType === 'setAdmin') {
      this.setAdmin();
    } else if (this.actionType === 'demotivate') {
      this.demotivate();
    }
  }

  // Feljogosítás
  setAdmin(): void {
    if (this.selectedUser) {
      this.authService.setAdmin(this.selectedUser.id).pipe(
        switchMap(() => this.authService.addEmployee(this.selectedUser.id))
      ).subscribe(
        () => {
          this.successModalTitle = 'Feljogosítva';
          this.successModalMessage = `A felhasználó: ${this.selectedUser.name} sikeresen feljogosítva és rögzítve dolgozóként!`;
  
          const actionModal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
          actionModal.hide();
  
          const successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
        },
        (error) => {
          console.error('Hiba történt a feljogosítás során', error);
          alert('Hiba történt a feljogosítás során');
        }
      );
    }
  }
  

  // Lefokozás
  demotivate(): void {
    if (this.selectedUser) {
      this.authService.demotivate(this.selectedUser.id).pipe(
        switchMap(() => this.authService.removeEmployee(this.selectedUser.id))
      ).subscribe(
        () => {
          this.successModalTitle = 'Lefokozva';
          this.successModalMessage = `A felhasználó: ${this.selectedUser.name} sikeresen lefokozva és a dolgozók listájából eltávolítva`;
  
          const actionModal = bootstrap.Modal.getInstance(document.getElementById('actionModal'));
          actionModal.hide();
  
          const successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
        },
        (error) => {
          console.error('Hiba történt a lefokozás során', error);
          alert('Hiba történt a lefokozás során');
        }
      );
    }
  }
  

  // Sikeres művelet modal bezárása
  closeSuccessModal(): void {
    const successModal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
    successModal.hide();
    this.loadUsers(); // Felhasználók újratöltése
  }
}
