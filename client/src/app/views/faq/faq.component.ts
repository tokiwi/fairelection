import { Component, OnInit } from '@angular/core';
import { TitleService } from '../../core/services/title.service';

@Component({
    selector: 'app-faq',
    templateUrl: './faq.component.html',
    styleUrls: ['./faq.component.scss']
})
export class FaqComponent implements OnInit {

    constructor(
        private titleService: TitleService,
    ) {
        this.titleService.setTitle('component.faq.title0');
    }

    ngOnInit(): void {
    }

}
