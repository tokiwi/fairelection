import { Component, Input, OnInit } from '@angular/core';
import { Election } from '../../../core/models/election.model';

@Component({
    selector: 'app-stepper',
    templateUrl: './stepper.component.html',
    styleUrls: ['./stepper.component.scss']
})
export class StepperComponent implements OnInit {

    @Input()
    public election: Election;

    @Input()
    public activeSteps = [];

    public steps = [
        {stepNumber: 1, text: 'component.choose_criteria.title1', path: 'criterias'},
        {stepNumber: 2, text: 'component.criteria_assignment.title1', path: 'assignments'},
        {stepNumber: 3, text: 'component.candidates.title1', path: 'candidates'},
        {stepNumber: 4, text: 'component.statistics.title1', path: 'statistics'},
        {stepNumber: 5, text: 'component.votes.title1', path: 'votes'},
        {stepNumber: 6, text: 'component.results.title1', path: 'results'},
    ];

    constructor() { }

    ngOnInit(): void {
    }

    public isActive(step: {stepNumber: number; text: string}): boolean {
        return this.activeSteps.includes(step.stepNumber);
    }

    public isActiveOrPassed(step: {stepNumber: number; text: string}): boolean {
        return step.stepNumber <= this.election.stepperPosition;
    }
}
