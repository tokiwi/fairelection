import { Component, Input, OnChanges, OnInit, SimpleChanges } from '@angular/core';
import { ElectionCriteria } from '../../../core/models/election-criteria.model';
import { MatSliderChange } from '@angular/material/slider';
import { Assignment } from '../../../core/models/assignment.model';

@Component({
    selector: 'app-criteria-percent-assignment',
    templateUrl: './criteria-percent-assignment.component.html',
    styleUrls: ['./criteria-percent-assignment.component.scss']
})
export class CriteriaPercentAssignmentComponent implements OnInit, OnChanges {
    @Input()
    public electionCriteria: ElectionCriteria;

    @Input()
    public map: Map<string, Map<string, number>>;

    @Input()
    public numberOfPeopleToElect = 1;

    public percentsMap = new Map();

    constructor(
    ) {
    }

    ngOnInit(): void {
        this.electionCriteria.assignments.forEach((assignment: Assignment) => {
            this.percentsMap.set(assignment['@id'], assignment.percent);
        });

        this.map.set(this.electionCriteria['@id'], this.percentsMap);
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (!changes.numberOfPeopleToElect) {
            return;
        }

        if (changes.numberOfPeopleToElect.currentValue < changes.numberOfPeopleToElect.previousValue) {
            this.percentsMap.forEach((value: number, key: string) => {
                this.percentsMap.set(key, 0);
            });
        }
    }

    /**
     * Decrements the sliders if the sum of these is greater than 100%.
     */
    public onSliderChange($event: MatSliderChange, id: string): void {
        this.percentsMap.set(id, $event.value);

        let sum = 0;
        this.percentsMap.forEach((percent, iri) => {
            if (iri !== id) {
                sum += percent;
            }
        });

        const excessPercent = $event.value - (this.numberOfPeopleToElect - sum);

        [...Array(excessPercent > 0 ? excessPercent : 0).keys()].forEach(() => {
            this.percentsMap.forEach((percent, iri) => {
                if (iri !== id && this.percentsMap.get(iri) > 0) {
                    this.percentsMap.set(iri, this.percentsMap.get(iri) - 1);
                }
            });
        });
    }
}
