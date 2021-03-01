import { Injectable } from '@angular/core';
import {
    HttpEvent,
    HttpInterceptor,
    HttpHandler,
    HttpRequest,
    HttpErrorResponse
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { AppErrorHandler } from '../handlers/app-error.handler';

/** Passes HttpErrorResponse to application-wide error handler */
@Injectable()
export class HttpErrorInterceptor implements HttpInterceptor {
    constructor(
        private errorHandler: AppErrorHandler,
    ) {}

    intercept(
        request: HttpRequest<any>,
        next: HttpHandler
    ): Observable<HttpEvent<any>> {
        return next.handle(request).pipe(
            tap({
                error: (err: any) => {
                    if (err instanceof HttpErrorResponse && null === request.params.get('error')) {
                        this.errorHandler.handleError(err);
                    }
                }
            })
        );
    }
}
