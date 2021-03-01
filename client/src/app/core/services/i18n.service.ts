import { Injectable } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { environment } from '../../../environments/environment';
import { LocalStorageService } from './local-storage.service';
import { BehaviorSubject } from 'rxjs';

@Injectable()
export class I18nService {
    public static languages: Array<{locale: string; name: string}> = [
        {locale: 'fr-ch', name: 'word.fr'},
        {locale: 'de', name: 'word.de'}
    ];

    public defaultLang = environment.defaultLang;
    public currentLangObs = new BehaviorSubject<string>(this.defaultLang);

    constructor(
        public translate: TranslateService,
    ) {
    }

    /**
     * Initialize i18n service.
     */
    public init(): void {
        I18nService.languages.map(value => {
            this.translate.addLangs([value.locale]);
        });
        this.translate.setDefaultLang(this.defaultLang);

        const locale = LocalStorageService.get(environment.localeStorageKey) || this.getBrowserLang();
        this.defaultLang = locale;
        this.useLocale(locale);
        this.currentLangObs.next(locale);
    }

    /**
     * Get the locale actually used by the application.
     */
    public getCurrentLocale(): string {
        return this.currentLangObs.getValue();
    }

    /**
     * Set the locale to use.
     *
     * @param locale the locale in ISO 639-1 language code (e.g. fr)
     */
    public useLocale(locale: string): void {
        if (!RegExp('^[A-Za-z]{2,4}([_-][A-Za-z]{4})?([_-]([A-Za-z]{2}|[0-9]{3}))?$').exec(locale)) {
            throw new Error('Invalid locale code used.');
        }

        LocalStorageService.set(environment.localeStorageKey, locale);
        this.currentLangObs.next(locale.toUpperCase());
        this.translate.use(locale);
    }

    /**
     * Get the browser locale if it is loaded by the application otherwise use the default one.
     */
    private getBrowserLang(): string {
        // no default user lang defined, get the browser lang
        const browserLang = this.translate.getBrowserLang();

        return this.translate.getLangs().includes(browserLang) ? browserLang : this.translate.getDefaultLang();
    }
}
