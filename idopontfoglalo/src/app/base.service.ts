import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BaseService {
  apiUrl="http://localhost:3000/techcucc/"  //Felhasználó oldali elérés

  firebaseApi="https://dolgozat-79584-default-rtdb.europe-west1.firebasedatabase.app./"   //Backend és rajta keresztül az adatbázis elérése

  private adatSub=new Subject()
  constructor(private http:HttpClient) {
    this.downloadAll()
   }

   //C.R.U.D.

  getAll(){
    return this.adatSub
  }

  private downloadAll(){
    this.http.get(this.firebaseApi+".json").subscribe(
      (res:any)=>{
          let adattomb=[]
          for (const key in res) {
            adattomb.push({azon:key, ...res[key]})
            }
          this.adatSub.next(adattomb)
          }    
    )
  }

  newData(data:any){
    this.http.post(this.firebaseApi+".json",data).forEach(
      ()=>this.downloadAll()
    )
  }
  updateData(data:any){
    this.http.put(this.firebaseApi+data.azon+".json",data).forEach(
      ()=>this.downloadAll()
    )
  }

  deleteData(data:any){
    this.http.delete(this.firebaseApi+data.azon+".json").forEach(
      ()=>this.downloadAll()
    )
  }
}