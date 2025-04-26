import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'pricelist',
  templateUrl: './pricelist.component.html',
  styleUrls: ['./pricelist.component.css']
})
export class PriceListComponent implements OnInit {

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
  
  

}
