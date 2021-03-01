import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { CandidateService } from '../services/candidate.service';
import { Candidate } from '../models/candidate.model';

@Injectable()
export class CandidatesResolver implements Resolve<Candidate[]> {
    constructor(
        private candidateService: CandidateService,
    ) { }
    /* eslint-disable */
    public resolve(
        route: ActivatedRouteSnapshot,
        state: RouterStateSnapshot
    ): Observable<Candidate[]> {
        return this.candidateService.findByElection(route.paramMap.get('electionId'));
    }
    /* eslint-enable */
}
