import {LoaderButton} from "./LoaderButton.tsx";
import type {SearchDateInputProps} from "../../types/SearchDateInputProps.ts";


export default function SearchDateInput({
                                        label,
                                        setQuery,
                                        query,
                                        error,
                                        loading,
                                        className ="input input-xs",
                                        readonly = false
                                    }:Readonly<SearchDateInputProps>) {


    return (
        <label className={className}>
            {label}
            <input
                type="date"
                required
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                readOnly={readonly || false}
                disabled={readonly || false}
            />
            {loading ? <LoaderButton/> :

                <svg className="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g
                        strokeLinejoin="round"
                        strokeLinecap="round"
                        strokeWidth="2.5"
                        fill="none"
                        stroke="currentColor"
                    >
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
            }
        </label>
    )
}