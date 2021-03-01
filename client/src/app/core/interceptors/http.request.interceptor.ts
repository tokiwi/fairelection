import { Injectable } from '@angular/core';
import { LoadingService } from '../services/loading.service';
import {
    HttpEvent,
    HttpHandler,
    HttpInterceptor,
    HttpRequest,
    HttpResponse
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class HttpRequestInterceptor implements HttpInterceptor {
    constructor(
        private loading: LoadingService
    ) {
    }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        this.loading.setLoading(request.url, true);

        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        return next.handle(request)
            .pipe(catchError((err) => {
                this.loading.setLoading(request.url, false);
                return throwError(err);
            }))
            .pipe(map<HttpEvent<any>, any>((evt: HttpEvent<any>) => {
                if (evt instanceof HttpResponse) {
                    this.loading.setLoading(request.url, false);
                }
                return evt;
            }));
    }
}
