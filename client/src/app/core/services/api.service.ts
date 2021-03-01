import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable ,  throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ApiService {
    constructor(
        private http: HttpClient
    ) {}

    private static formatErrors(error: HttpErrorResponse): Observable<never> {
        return throwError(error.error);
    }

    public get(path: string, options?: Record<any, any>): Observable<any> {
        return this.http.get(`${environment.apiUrl}${path}`, options)
            .pipe(catchError(ApiService.formatErrors));
    }

    public put(path: string, body: Record<string, unknown> = {}): Observable<any> {
        return this.http.put(
            `${environment.apiUrl}${path}`,
            JSON.stringify(body)
        ).pipe(catchError(ApiService.formatErrors));
    }

    public post(path: string, body: Record<string, any> = {}, options?: Record<any, any>): Observable<any> {
        return this.http.post(
            `${environment.apiUrl}${path}`,
            body instanceof FormData ? body : JSON.stringify(body),
            options
        ).pipe(catchError(ApiService.formatErrors));
    }

    public delete(path: string): Observable<any> {
        return this.http.delete(
            `${environment.apiUrl}${path}`
        ).pipe(catchError(ApiService.formatErrors));
    }

    public patch(path: string, body: Record<string, unknown> = {}): Observable<any> {
        return this.http.patch(
            `${environment.apiUrl}${path}`,
            JSON.stringify(body)
        ).pipe(catchError(ApiService.formatErrors));
    }
}
