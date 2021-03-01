import { Component, OnInit } from '@angular/core';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute } from '@angular/router';
import { TitleService } from '../../../core/services/title.service';
import { SolverResourceService } from '../../../core/services/solver-resource.service';
import { IriUtil } from '../../../core/utils/iri.util';
import { catchError, finalize, take } from 'rxjs/operators';
import { throwError } from 'rxjs';

@Component({
    selector: 'app-statistics-view',
    templateUrl: './statistics.view.html',
    styleUrls: ['./statistics.view.scss']
})
export class StatisticsViewComponent implements OnInit {
    public election: Election;
    public hasError = false;
    public loading = true;

    constructor(
        private route: ActivatedRoute,
        private titleService: TitleService,
        public solverResourceService: SolverResourceService,
    ) {
        this.titleService.setTitle('component.statistics.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;
        });
    }

    public onError(event: boolean): void {
        this.hasError = event;
    }

    public ngOnInit(): void {
        this.solverResourceService.solveByElection(IriUtil.extractId(this.election['@id']), false).pipe(
            take(1),
            catchError((err) => {
                this.solverResourceService.isSolvableSubject.next(false);
                return throwError(err);
            }),
            finalize(() => this.loading = false)
        ).subscribe(() => {
            this.solverResourceService.isSolvableSubject.next(true);
        });
    }
}
