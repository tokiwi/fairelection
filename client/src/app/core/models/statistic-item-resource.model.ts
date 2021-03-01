export interface StatisticItemResource {
    '@id': string;
    '@type': string;
    categoryName: string;
    categoryPictogram: string;
    errors: boolean;
    items: {candidateNumber: number; category: string; sufficient: boolean; percent: number; red: number; green: number}[];
}
