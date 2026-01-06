import React from "react";
import {LoaderButton} from "../../../../../shared/components/ui/LoaderButton.tsx";


type Props = {
    handleValidate: () => void;
    loading: boolean;
};
export default function
    TimesheetValidateView({
                              handleValidate,
                              loading
                                            }: Readonly<Props>) {

    return (

        <button
            onClick={handleValidate}
                disabled={loading}
            className={"btn btn-success btn-xs flex gap-2"}
        >
            Valider la feuille de temps
            {
                loading ? (
                    <LoaderButton/>
                ) : (<svg
                    className="w-4 h-4 text-gray-800" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                          d="M5 11.917 9.724 16.5 19 7.5"/>
                </svg>)
            }
        </button>
    );
}
