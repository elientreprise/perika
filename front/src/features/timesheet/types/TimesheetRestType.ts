import type {RowDescriptor} from "../../../shared/types/RowDescriptor.ts";


export const TimesheetRestType: RowDescriptor[] = [
    {key: "isMinDailyRestMet", label : "Temps de repos de 11h entre 2 jours travaillés respecté"},
    {key: "isWorkShiftValid", label : "Mon temps de travail effectif a débuté entre 8h00 et 10h00 et Mon temps de travail effectif a pris fin entre 16h30 et 19h00"},
    {key: "workedMoreThanHalfDay", label : "J’ai travaillé plus d’une demi-journée"},
    {key: "lunchBreak", label : "Durée de la pause déjeuner"},
]
