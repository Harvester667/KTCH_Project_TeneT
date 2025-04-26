import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

declare var bootstrap: any; // Bootstrap modal importálása

@Component({
  selector: 'app-services-list',
  templateUrl: './services-list.component.html',
  styleUrls: ['./services-list.component.css']
})
export class ServicesListComponent implements OnInit {

  services: any[] = [];
  newService = { service: '', price: null };
  editingService: any = null;
  serviceToDelete: any = null;

  constructor(private authService: AuthService, private router: Router) {}

  ngOnInit(): void {
    this.getServices();
  }

  // Szolgáltatások lekérése
  getServices(): void {
    this.authService.getServices().subscribe(
      (res) => {
        console.log('Backend válasz:', res);
        this.services = res.data || res;
      },
      (error) => {
        console.error('Hiba a szolgáltatások lekérésekor:', error);
      }
    );
  }

  // Új szolgáltatás hozzáadása
  addService(): void {
    if (this.newService.service && this.newService.price !== null) {
      const serviceData = {
        service: this.newService.service,
        price: this.newService.price
      };

      this.authService.addService(serviceData).subscribe(
        (response) => {
          if (response.success) {
            this.services.push(response.data);
            this.newService = { service: '', price: null };
            // Bezárjuk a modalt a sikeres hozzáadás után
            const modal = document.getElementById('addServiceModal') as any;
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
          } else {
            console.error('Hiba történt a szolgáltatás hozzáadásakor');
          }
        },
        (error) => {
          console.error('Hiba történt a szolgáltatás hozzáadásakor:', error);
        }
      );
    } else {
      console.error('Kérem adja meg a szolgáltatás nevét és árát.');
    }
  }

  // Szolgáltatás szerkesztése
  editService(service: any): void {
    this.editingService = { ...service };  // csak másold át, ne írj felül id-t
  
    console.log('Szerkesztésre előkészített szolgáltatás:', this.editingService);
  
    const modalElement = document.getElementById('editServiceModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
  }


  // Szolgáltatás törlése
  deleteService(service: any): void {
    this.serviceToDelete = service;
    const modal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));
    modal.show();
  }

  confirmDelete(): void {
    if (!this.serviceToDelete) return;
  
    const serviceId = this.serviceToDelete.id;
  
    this.authService.deleteService(serviceId).subscribe({
      next: (response) => {
        if (response.success) {
          this.services = this.services.filter(service => service.id !== serviceId);
  
          const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteServiceModal'));
          deleteModal.hide();
  
          const successModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
          successModal.show();
  
          this.serviceToDelete = null;
        }
      },
      error: (err) => {
        console.error('Hiba történt a szolgáltatás törlésekor:', err);
      }
    });
  }
  

  // Szolgáltatás módosítása
  saveEditedService(): void {
    // Ellenőrizzük, hogy az editingService létezik, és hogy tartalmazza-e az id-t, name-t, price-t
    if (!this.editingService) {
      console.error('Az editingService objektum nem létezik!');
      return;
    }

    // Ellenőrizzük az egyes mezőket
    if (!this.editingService.id) {
      console.error('Hiányzik az id!');
      return;
    }
    if (!this.editingService.service) {
      console.error('Hiányzik a name!');
      return;
    }
    if (!this.editingService.price) {
      console.error('Hiányzik a price!');
      return;
    }

    console.log('Mentés előtt a szerkesztett szolgáltatás:', this.editingService);

    // Az ár feldolgozása
    let price = this.editingService.price;
    if (typeof price === 'string') {
      price = price.replace(/[^0-9.-]+/g, '');  // Eltávolítja a nem szám karaktereket
    }

    // Az ár konvertálása számra
    const numericPrice = parseFloat(price);
  
    if (isNaN(numericPrice)) {
      console.error('A megadott ár nem érvényes szám!');
      return;
    }

    const updatedService = {
      service: this.editingService.service,
      price: numericPrice  // Ár szám formátumban
    };

    this.authService.updateService(this.editingService.id, updatedService).subscribe({
      next: (response) => {
        console.log('Szolgáltatás frissítve', response);
        this.getServices();  // Újra betölti a szolgáltatásokat
        // Modal bezárása sikeres mentés után
        const modalElement = document.getElementById('editServiceModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();
      },
      error: (err) => {
        console.error('Hiba történt a mentés során:', err);
      },
    });
  }

  // Módosítás törlése
  cancelEdit(): void {
    this.editingService = null; // Kilépünk a szerkesztésből
    // Modal bezárása, ha a szerkesztés törlésre kerül
    const modalElement = document.getElementById('editServiceModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    modal.hide();
  }
}
