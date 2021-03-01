import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import {  map } from 'rxjs/operators';
import { ApiService } from './api.service';
import { Election } from '../models/election.model';
import { environment } from '../../../environments/environment';
import { HttpParams } from '@angular/common/http';
import { Paginator } from '../models/paginator.model';

@Injectable()
export class ElectionService {
    constructor(
        private api: ApiService
    ) {}

    public findAllPaginated(pageNumber = 1, itemsPerPage: number = environment.itemsPerPage): Observable<Paginator> {
        const params = new HttpParams()
            .set('page', pageNumber.toString())
            .set('itemsPerPage', itemsPerPage.toString());

        return this.api.get('/elections', {params}) as Observable<Paginator>;
    }

    public find(id: string): Observable<Election> {
        return this.api.get('/elections/' + id).pipe(
            map((res) => res as Election)
        );
    }

    public save(body: Record<any, unknown>): Observable<Election> {
        return this.api.post('/elections', body).pipe(
            map((res) => res as Election)
        );
    }

    public delete(id: string): Observable<void> {
        return this.api.delete('/elections/' + id).pipe(
            map((res) => res as void)
        );
    }

    public patch(body: Record<any, unknown>): Observable<Election> {
        return this.api.post('/elections', body).pipe(
            map((res) => res as Election)
        );
    }

    public downloadCandidateModel(id: string): Observable<any> {
        return this.api.get('/elections/' + id + '/candidate-model', {responseType: 'blob'});
    }

    public downloadVoteModel(id: string): Observable<any> {
        return this.api.get('/elections/' + id + '/vote-model', {responseType: 'blob'});
    }

    public uploadCandidateModel(file: File, params: HttpParams = null): Observable<any> {
        const formData = new FormData();
        formData.append('file', file, file.name);

        return this.api.post('/elections/candidate-model', formData, {params});
    }

    public uploadVoteModel(file: File, params: HttpParams = null): Observable<any> {
        const formData = new FormData();
        formData.append('file', file, file.name);

        return this.api.post('/elections/vote-model', formData, {params});
    }
}
