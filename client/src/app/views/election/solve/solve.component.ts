import { Component } from '@angular/core';
import { SolverResourceService } from '../../../core/services/solver-resource.service';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute, Router } from '@angular/router';
import { IriUtil } from '../../../core/utils/iri.util';
import { take } from 'rxjs/operators';

@Component({
    selector: 'app-solve',
    templateUrl: './solve.component.html',
    styleUrls: ['./solve.component.scss']
})
export class SolveComponent {
    public error = false;
    public election: Election;

    constructor(
        private route: ActivatedRoute,
        private solverResourceService: SolverResourceService,
        private router: Router,
    ) {
        this.route.data.subscribe(data => {
            this.election = data.election as Election;
            this.solverResourceService.solveByElection(IriUtil.extractId(this.election['@id'])).pipe(
                take(1)
            ).subscribe(() => {
                void this.router.navigate([
                    'elections',
                    IriUtil.extractId(this.election['@id']),
                    'results'
                ]);
            }, () => {
                this.error = true;
            });
        });
    }

    public toCriteria(): void {
        void this.router.navigate([
            'elections',
            IriUtil.extractId(this.election['@id']),
            'assignments'
        ]);
    }
}
