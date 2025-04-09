import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-services-list',
  templateUrl: './services-list.component.html',
  styleUrls: ['./services-list.component.css']
})
export class ServicesListComponent implements OnInit {

  services: any[] = [];
  newService = { name: '', price: null };
  editingService: any = null;

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
    if (this.newService.name && this.newService.price !== null) {
      const serviceData = {
        service: this.newService.name,
        price: this.newService.price
      };

      this.authService.addService(serviceData).subscribe(
        (response) => {
          if (response.success) {
            this.services.push(response.data);
            this.newService = { name: '', price: null };
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


    this.editingService = { 
      ...service, 
      id: service.value // A value mező értéke lesz az id
    };
  
    console.log('Szerkesztésre előkészített szolgáltatás:', this.editingService);
  }
  
  

  // Szolgáltatás törlése
  deleteService(serviceId: number): void {
    if (confirm('Biztosan törölni szeretné ezt a szolgáltatást?')) {
      this.authService.deleteService(serviceId).subscribe(
        (response) => {
          if (response.success) {
            this.services = this.services.filter(service => service.id !== serviceId); // Eltávolítjuk a törölt szolgáltatást
            console.log('Szolgáltatás törölve');
          } else {
            console.error('Hiba történt a szolgáltatás törlésekor');
          }
        },
        (error) => {
          console.error('Hiba történt a szolgáltatás törlésekor:', error);
        }
      );
    }
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
  if (!this.editingService.name) {
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
    service: this.editingService.name,
    price: numericPrice  // Ár szám formátumban
  };

  this.authService.updateService(this.editingService.id, updatedService).subscribe({
    next: (response) => {
      console.log('Szolgáltatás frissítve', response);
      this.getServices();  // Újra betölti a szolgáltatásokat
    },
    error: (err) => {
      console.error('Hiba történt a mentés során:', err);
    },
  });
}


  // Módosítás törlése
  cancelEdit(): void {
    this.editingService = null; // Kilépünk a szerkesztésből
  }
}
