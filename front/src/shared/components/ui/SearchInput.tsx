import {LoaderButton} from "./LoaderButton.tsx";
import type {SearchInputProps} from "../../types/SearchInputProps.ts";


export default function SearchInput({
                                        label,
                                        setQuery,
                                        query,
                                        error,
                                        loading,
                                        placeholder = "Rechercher...",
                                        className ="input input-xs",
                                        readonly
                                    }:Readonly<SearchInputProps>) {


    return (
        <label className={className}>
            { label }
            <input
                type="search"
                required
                value={query}
                placeholder={placeholder}
                readOnly={readonly || false}
                disabled={readonly || false}
                onChange={setQuery}
            />
            { loading ? <LoaderButton/> :

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