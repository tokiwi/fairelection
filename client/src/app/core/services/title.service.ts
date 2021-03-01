import { Injectable } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { TranslateService } from '@ngx-translate/core';

@Injectable()
export class TitleService {
    constructor(
        protected title: Title,
        protected translateService: TranslateService
    ) {

    }

    public setTitle(title: string): void {
        this.translateService.get(['title.fair_election', title]).subscribe((translations: string[]) => {
            const part1 = translations[title] as string;
            const part2 = translations['title.fair_election'] as string;

            this.title.setTitle(part1 + ' | ' + part2);
        });
    }
}
