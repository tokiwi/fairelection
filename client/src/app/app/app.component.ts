import { Component } from '@angular/core';
import { I18nService } from '../core/services/i18n.service';
import { AuthService } from '../core/services/auth.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent {
    title = 'fair-election-client';

    constructor(
        private i18nService: I18nService,
        private authService: AuthService,
    ) {
        this.i18nService.init();

        // try to authenticate user from local storage
        this.authService.autoLogin();
    }
}
