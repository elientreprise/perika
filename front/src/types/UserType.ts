export type UserType = {
    uuid: string,
    email: string,
    firstName: string,
    lastName: string,
    position: null,
    salary: number,
    hire_date: Date,
    phoneNumber: string,
    birthDate: string,
    manager: UserType | null,
    fullName: string,
    seniority: string
};