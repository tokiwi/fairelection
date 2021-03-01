import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { CriteriaService } from '../services/criteria.service';
import { Criteria } from '../models/criteria.model';

@Injectable()
export class CriteriasResolver implements Resolve<Criteria[]> {
    constructor(
        private criteriaService: CriteriaService,
    ) { }
    /* eslint-disable */
    public resolve(
        route: ActivatedRouteSnapshot,
        state: RouterStateSnapshot
    ): Observable<Criteria[]> {
        return this.criteriaService.findAll(route.paramMap.get('electionId'));
    }
    /* eslint-enable */
}
