import { Injectable } from '@angular/core';
import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { I18nService } from '../services/i18n.service';

@Injectable()
export class HttpLanguageInterceptor implements HttpInterceptor {
    constructor(
        private i18nService: I18nService
    ) {}

    public intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const headersConfig = {
            'Accept-Language': this.i18nService.getCurrentLocale().toLowerCase()
        };

        const request = req.clone({ setHeaders: headersConfig });

        return next.handle(request);
    }
}
