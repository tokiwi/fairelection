import { Component, OnInit } from '@angular/core';
import { TitleService } from '../../core/services/title.service';

@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
    constructor(
        private titleService: TitleService,
    ) {
        this.titleService.setTitle('component.home.title1');
    }

    ngOnInit(): void {
    }
}
