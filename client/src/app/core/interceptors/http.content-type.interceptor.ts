import { Injectable } from '@angular/core';
import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable()
export class HttpContentTypeInterceptor implements HttpInterceptor {
    public intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const headersConfig = {
            Accept: 'application/ld+json',
        };

        if (!(req.body instanceof FormData)) {
            headersConfig['Content-Type'] = req.method === 'PATCH' ? 'application/merge-patch+json' : 'application/ld+json';
        }

        const request = req.clone({ setHeaders: headersConfig });
        return next.handle(request);
    }
}
