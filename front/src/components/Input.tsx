import React from "react";

type InputProps = {
    label: string;
    type?: string;
    value: string;
    placeholder?: string;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
};

export const Input: React.FC<InputProps> = ({
                                                label,
                                                type = "text",
                                                value,
                                                placeholder = "",
                                                onChange,
                                            }) => (
    <div className="form-control w-full mb-4">
        <label className="label">
            <span className="label-text">{label}</span>
        </label>
        <input
            type={type}
            value={value}
            placeholder={placeholder}
            onChange={onChange}
            className="input input-bordered w-full"
        />
    </div>
);
