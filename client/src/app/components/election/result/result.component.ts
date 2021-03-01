import { Component, Input, OnInit } from '@angular/core';
import { Election } from '../../../core/models/election.model';
import { IriUtil } from '../../../core/utils/iri.util';
import { ResultResourceService } from '../../../core/services/result-resource.service';
import { ResultResource } from '../../../core/models/result-resource.model';
import { ResultItemResource } from '../../../core/models/result-item-resource.model';
import { finalize } from 'rxjs/operators';
import { BehaviorSubject } from 'rxjs';

@Component({
    selector: 'app-result-component',
    templateUrl: './result.component.html',
    styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {
    @Input()
    public election: Election;

    public stats: any;
    public resultResources: ResultItemResource[];
    public totalNumberOfVotes = 0;
    public messageMapping: {[k: string]: string} = {'=0': 'word.nobody', '=1': 'word.one_person', other: 'word.many_person'};

    private loadingSubject = new BehaviorSubject<boolean>(false);
    public loading$ = this.loadingSubject.asObservable(); // eslint-disable-line

    constructor(
        private resultResourceService: ResultResourceService
    ) {
    }

    ngOnInit(): void {
        this.loadingSubject.next(true);

        this.resultResourceService.getByElection(IriUtil.extractId(this.election['@id'])).pipe(
            finalize(() => this.loadingSubject.next(false))
        ).subscribe((resultResources: ResultResource) => {
            this.stats = resultResources.stats;
            this.resultResources = resultResources.rows;
            resultResources.rows.forEach((res: ResultItemResource) => this.totalNumberOfVotes += res.votes);
        });
    }
}
