import { Injectable } from '@angular/core';
import { HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { JwtService } from '../services/jwt.service';
import { catchError } from 'rxjs/operators';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Injectable()
export class HttpAuthorizationInterceptor implements HttpInterceptor {
    constructor(
        private router: Router,
        private authService: AuthService
    ) {}

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        let headersConfig = {};

        const token = JwtService.getToken();
        if (token) {
            headersConfig = {
                Authorization: 'Bearer ' + JwtService.getToken(),
            };
        }

        const request = req.clone({ setHeaders: headersConfig });

        return next.handle(request).pipe(
            catchError(x => this.handleAuthError(x))
        );
    }

    private handleAuthError(response: HttpErrorResponse): Observable<HttpEvent<any>> {
        if (response.status === 401) {
            this.authService.logout();

            if (!response.url.endsWith('authenticate')) {
                // eslint-disable-next-line
                void this.router.navigateByUrl('/');
            }
        }

        return throwError(response);
    }

}
