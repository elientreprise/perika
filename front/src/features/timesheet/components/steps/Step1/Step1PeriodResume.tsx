import type {TimesheetType} from "../../../types/TimesheetType.ts";

type Props = {
    timesheet: TimesheetType;
};
export default function Step1PeriodResume({
                                            timesheet
                                          }: Readonly<Props>) {
    return (

           <div>
                <h3 className="text-xl font-semibold mb-4">Sommaire feuille de temps</h3>
                <div className="mb-2">{timesheet.employee.fullName}</div>

                <div className="flex gap-52">
                    <div className="flex flex-col gap-1 text-xs">
                        {/*todo: initialiser un numéro d'employée en back*/}
                        <div><label>Code employé : </label> <b> {timesheet.employee.uuid}</b></div>
                        {/*todo: renvoyer une date formatée depuis le back*/}
                        <label>Date de fin de période : <b>{timesheet.endPeriod}</b></label>
                        {/*todo: initialiser un numéro de feuille de temps en back */}
                        <label>Feuille de temps : <b> {timesheet.uuid} </b> </label>
                        {/*todo: ajouter un status à la feuille de temps en back*/}
                        <label>Status : <b>En cours de révision</b></label>
                    </div>
                    <div className="flex flex-col gap-1 text-xs">
                        {/*todo: gérer les entitées en back*/}
                        <label>Entité : 1235</label>
                        <label>Division : ABC001</label>
                        <div><label>Type de service : </label> <span> 180</span></div>
                        <label>Emploi : </label>
                    </div>
                </div>
           </div>
    )

}
