import { Component, OnInit } from '@angular/core';
import { I18nService } from '../../core/services/i18n.service';
import { Observable } from 'rxjs';
import { BreakpointObserver, Breakpoints } from '@angular/cdk/layout';
import { map, shareReplay } from 'rxjs/operators';
import { LoadingService } from '../../core/services/loading.service';
import { AuthService } from '../../core/services/auth.service';
import { Title } from '@angular/platform-browser';
import { Router } from '@angular/router';

@Component({
    selector: 'app-app-layout',
    templateUrl: './app-layout.component.html',
    styleUrls: ['./app-layout.component.scss']
})
export class AppLayoutComponent implements OnInit {

    public isHandset$: Observable<boolean> = this.breakpointObserver
        .observe(Breakpoints.Handset)
        .pipe(
            map(result => result.matches),
            shareReplay()
        );

    public isLargeAndUp$: Observable<boolean> = this.breakpointObserver
        .observe([Breakpoints.Large, Breakpoints.XLarge])
        .pipe(
            map(result => result.matches),
            shareReplay()
        );

    public languages = I18nService.languages;
    public currentLocale;

    public menu = [
        { link: '/elections/create', label: 'menu.new_election'},
        { link: '/faq', label: 'menu.faq'}
    ];

    constructor(
        public pageTitle: Title,
        public i18n: I18nService,
        private breakpointObserver: BreakpointObserver,
        public loadingService: LoadingService,
        public authService: AuthService,
        private router: Router,
    ) {
    }

    ngOnInit(): void {
        this.authService.isAuthenticated.subscribe((isAuthenticated: boolean) => {
            if (isAuthenticated) {
                this.menu.unshift({ link: '/elections', label: 'menu.my_elections'});
            }
        });
    }

    public changeLanguage(lang: string): void {
        this.i18n.useLocale(lang);
        this.currentLocale = lang.toUpperCase();
    }

    public logout(): void {
        this.authService.logout();
        void this.router.navigateByUrl('/');
    }
}
