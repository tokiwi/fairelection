import { Injectable, ErrorHandler } from '@angular/core';
import { HttpErrorResponse } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Router } from '@angular/router';
import { NotificationService } from '../services/notification.service';


@Injectable()
export class AppErrorHandler implements ErrorHandler {
    constructor(
        private notificationsService: NotificationService,
        private route: Router
    ) {
    }

    handleError(errorResponse: HttpErrorResponse): void {
        if (!(errorResponse instanceof HttpErrorResponse)) {
            return;
        }

        let displayMessage = '';

        if (errorResponse.error) {
            // eslint-disable-next-line
            const message = errorResponse.error['hydra:description'] || errorResponse.error.message;

            // as the api return "<field>:<message>" string, remove field part for better UI
            displayMessage += message.substring(message.indexOf(':') + 1); // eslint-disable-line
        }

        if ('' === displayMessage) {
            displayMessage += 'Oops, an error occurred.';
        }

        if (!environment.production) {
            displayMessage += ' See console for details.';
            console.log(errorResponse);
        }

        if ('' === displayMessage) {
            return;
        }

        if (errorResponse.status === 403) {
            void this.route.navigateByUrl('/');
        }

        this.notificationsService.error(displayMessage);
    }
}
