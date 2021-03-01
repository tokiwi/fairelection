import { BrowserModule } from '@angular/platform-browser';
import { LOCALE_ID, NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app/app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AppLayoutComponent } from './layouts/app-layout/app-layout.component';
import { HomeComponent } from './views/home/home.component';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';

import { registerLocaleData } from '@angular/common';
import localeFrCh from '@angular/common/locales/fr-CH';
import localeDeCh from '@angular/common/locales/de-CH';
import localeItCh from '@angular/common/locales/it-CH';
import { CoreModule } from './core/core.module';
import { MatButtonModule } from '@angular/material/button';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatMenuModule } from '@angular/material/menu';
import { MatListModule } from '@angular/material/list';
import { MatProgressBarModule } from '@angular/material/progress-bar';
import { MatIconModule } from '@angular/material/icon';
import { MatCardModule } from '@angular/material/card';
import { ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { ElectionModule } from './views/election/election.module';
import { MatInputModule } from '@angular/material/input';
import { RegisterComponent } from './views/register/register.component';
import { LoginComponent } from './views/login/login.component';
import { AppHomeLayoutComponent } from './layouts/app-home-layout/app-home-layout.component';
import { IvyCarouselModule } from 'angular-responsive-carousel';
import { FaqComponent } from './views/faq/faq.component';
import { MatExpansionModule } from '@angular/material/expansion';
import { I18nService } from './core/services/i18n.service';
import { SharedModule } from './shared/shared.module';
import { ResetPasswordRequestComponent } from './views/reset-password-request/reset-password-request.component';
import { ResetPasswordComponent } from './views/reset-password/reset-password.component';
import { DragScrollModule } from 'ngx-drag-scroll';
registerLocaleData(localeFrCh);
registerLocaleData(localeDeCh);
registerLocaleData(localeItCh);

export function createTranslateLoader(http: HttpClient): TranslateHttpLoader {
    return new TranslateHttpLoader(http, './assets/i18n/', '.json');
}

@NgModule({
    declarations: [
        AppComponent,
        AppLayoutComponent,
        HomeComponent,
        RegisterComponent,
        LoginComponent,
        AppHomeLayoutComponent,
        FaqComponent,
        ResetPasswordRequestComponent,
        ResetPasswordComponent,
    ],
    imports: [
        CoreModule,
        BrowserModule,
        AppRoutingModule,
        BrowserAnimationsModule,
        ElectionModule,
        HttpClientModule,
        MatMenuModule,
        MatSidenavModule,
        MatToolbarModule,
        MatListModule,
        MatExpansionModule,
        MatProgressBarModule,
        MatIconModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: (createTranslateLoader),
                deps: [HttpClient]
            },
        }),
        MatButtonModule,
        MatCardModule,
        ReactiveFormsModule,
        MatFormFieldModule,
        MatInputModule,
        IvyCarouselModule,
        SharedModule,
        DragScrollModule,
    ],
    providers: [
        {
            provide: LOCALE_ID,
            deps: [I18nService],
            useFactory: (i18nService: I18nService) => i18nService.getCurrentLocale()
        },
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
