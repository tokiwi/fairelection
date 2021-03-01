import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { StatisticResourceService } from '../../../core/services/statistic-resource.service';
import { Observable } from 'rxjs';
import { StatisticResource } from '../../../core/models/statistic-resource.model';
import { Election } from '../../../core/models/election.model';
import { IriUtil } from '../../../core/utils/iri.util';
import { StatisticItemResource } from '../../../core/models/statistic-item-resource.model';

@Component({
    selector: 'app-statistic',
    templateUrl: './statistic.component.html',
    styleUrls: ['./statistic.component.scss']
})
export class StatisticComponent implements OnInit {

    @Input()
    public election: Election;

    @Output()
    public hasError: EventEmitter<boolean> = new EventEmitter<boolean>();

    @Input()
    public electedOnly = false;

    public statisticResources$: Observable<StatisticItemResource[]>;
    public statisticResources: StatisticItemResource[];

    constructor(
        private statisticResourceService: StatisticResourceService
    ) {
    }

    ngOnInit(): void {
        // this.statisticResources$ = this.statisticResourceService.getByElection(IriUtil.extractId(this.election['@id']));

        this.statisticResourceService.getByElection(IriUtil.extractId(this.election['@id']), this.electedOnly)
            .subscribe((statisticResources: StatisticResource) => {
                this.statisticResources = statisticResources.rows;

                this.statisticResources.forEach((statisticItemResource: StatisticItemResource) => {
                    if (statisticItemResource.errors) {
                        this.hasError.emit(true);

                        return;
                    }
                });
            });
    }
}
