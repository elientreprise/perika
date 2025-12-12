import type {DateInputProps} from "../../types/DateInputProps.ts";

export default function DateInput({
                                      label,
                                      value,
                                      onChange,
                                      className = "",
                                      ...rest
                                  }: DateInputProps) {
    return (
        <div className={`flex flex-col ${className}`}>
            <p className="label">{label}</p>

            <label className="input">
                <input
                    type="date"
                    value={value}
                    onChange={(event) => onChange?.(event) }
                    {...rest}
                />
            </label>
        </div>
    );
}
