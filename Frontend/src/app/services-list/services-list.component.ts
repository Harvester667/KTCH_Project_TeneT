import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
declare var bootstrap: any;
import { HttpClient, HttpHeaders } from '@angular/common/http';
@Component({
  selector: 'app-services-list',
  templateUrl: './services-list.component.html',
  styleUrls: ['./services-list.component.css']
})
export class ServicesListComponent implements OnInit {
  services: any[] = [];
  newService: any = { service: '', price: null };
  editingService: any = null;
  serviceToDelete: any = null;

  constructor(private authService: AuthService, private http: HttpClient) {}

  ngOnInit(): void {
    this.getServices();
  }

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

  addService() {
    const serviceData = {
      ...this.newService,
      active: 1 // itt biztosítjuk, hogy az active mindig 1 legyen
    };
  
    this.authService.addService(serviceData).subscribe(
      () => {
        this.closeModal('addServiceModal');
        this.getServices();
        this.newService = { service: '', price: null }; // form ürítése
      },
      error => {
        console.error('Hiba az új szolgáltatás hozzáadásakor', error);
      }
    );
  }
  

  editService(service: any) {
    this.editingService = { ...service };
    
    setTimeout(() => {
      const editModal = document.getElementById('editServiceModal');
      if (editModal) {
        const modalInstance = new bootstrap.Modal(editModal);
        modalInstance.show();
      }
    }, 100); // 100ms idő, hogy a DOM frissüljön
  }

  saveEditedService() {
    if (this.editingService) {
      const token = localStorage.getItem('token'); // token elérése a storage-ból
  
      const headers = new HttpHeaders({
        'Authorization': `Bearer ${token}`
      });
  
      this.http.patch('http://localhost:8000/api/updateservice/' + this.editingService.id, this.editingService, { headers })
        .subscribe({
          next: (response) => {
            console.log('Szolgáltatás sikeresen módosítva', response);
            this.getServices(); // Frissítjük a szolgáltatásokat
  
            const editModal = document.getElementById('editServiceModal');
            if (editModal) {
              const modalInstance = bootstrap.Modal.getInstance(editModal);
              modalInstance?.hide();
            }
          },
          error: (error) => {
            console.error('Hiba a szolgáltatás módosítása közben', error);
          }
        });
    }
  }
  
  

  cancelEdit() {
    this.editingService = null;
    this.closeModal('editServiceModal');
  }

  deleteService(service: any) {
    this.serviceToDelete = service;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));
    deleteModal.show();
  }

  confirmDelete() {
    this.authService.deleteService(this.serviceToDelete.id).subscribe(
      () => {
        this.closeModal('deleteServiceModal');
        this.getServices();
        const deleteSuccessModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
        deleteSuccessModal.show();
        this.serviceToDelete = null;
      },
      error => {
        console.error('Hiba a szolgáltatás törlésekor', error);
      }
    );
  }

  closeModal(modalId: string) {
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      if (modalInstance) {
        modalInstance.hide();
      }
    }
  }
}
