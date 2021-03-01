import { Component, OnInit } from '@angular/core';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute } from '@angular/router';
import { TitleService } from '../../../core/services/title.service';

@Component({
    selector: 'app-result',
    templateUrl: './result.component.html',
    styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {
    public election: Election;

    constructor(
        private route: ActivatedRoute,
        private titleService: TitleService,
    ) {
        this.titleService.setTitle('component.results.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;
        });
    }

    ngOnInit(): void {
    }
}
