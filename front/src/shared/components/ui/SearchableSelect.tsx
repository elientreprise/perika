import { useState, useMemo } from "react";
import type {SearchableSelectProps} from "../../types/SearchableSelectProps.ts";
import {LoaderButton} from "./LoaderButton.tsx";





export default function SearchableSelect({
                                             options,
                                             value,
                                             onChange,
                                             setQuery,
                                             query,
                                             error,
                                             loading,
                                             placeholder = "Select...",
                                             className = "",
                                             readonly,
                                             ...rest
                                         }: SearchableSelectProps) {
    const [search, setSearch] = useState("");
    const [isOpen, setIsOpen] = useState(false);

    const filteredOptions = useMemo(
        () =>
            options.filter((opt) =>
                opt.label.toLowerCase().includes(search.toLowerCase())
            ),
        [options, search]
    );

    const selectedLabel = options.find((opt) => opt.value === value)?.label;

    return (
        <div className={`relative w-full ${className} text-xs`}>

            <div
                className={`${!readonly ? 'text-xs rounded cursor-pointer flex justify-between items-center' : ''} ${error ? "text-error" : ""}`}
                onClick={() => setIsOpen(!isOpen)}
            >
                {!readonly ? (
                    <>
                        <span>{selectedLabel || placeholder}</span>
                        <span className="ml-2">&#9662;</span>
                    </>

                ) : (
                    <span>{selectedLabel || ""}</span>
                )}
            </div>

            {isOpen && !readonly && (
                <div className="absolute z-10 w-full bg-base-100 border rounded mt-1 shadow max-h-60 overflow-y-auto">

                    <input
                        type="text"
                        className={`w-full p-2 border-b outline-none text-xs ${error ? "text-error" : ""}`}
                        placeholder="Rechercher..."
                        value={value || search}
                        onChange={(e) => setQuery ? setQuery(e.target.value) : setSearch(e.target.value)}
                        {...rest}
                    />

                    <ul>
                        {
                            loading ? (
                                <li className="flex justify-center text-xs p-3">
                                    <LoaderButton />
                                </li>
                                ) : filteredOptions.length > 0 ? (
                            filteredOptions.map((opt) => (
                                <li
                                    key={opt.value}
                                    className="p-2 text-xs cursor-pointer hover:bg-gray-100"
                                    onClick={() => {
                                        onChange?.(opt.value);
                                        setIsOpen(false);
                                        setSearch("");
                                    }}
                                >
                                    {opt.label}
                                </li>
                            ))
                            ) : (
                                <li className="p-2 text-xs text-gray-400">Aucune option</li>
                            )
                        }
                    </ul>
                </div>
            )}
        </div>
    );
}
