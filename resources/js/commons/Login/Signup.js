import Box from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import FormControl from '@mui/material/FormControl'
import Typography from '@mui/material/Typography'
import IconButton from '@mui/material/IconButton'
import InputAdornment from '@mui/material/InputAdornment'
import Visibility from '@mui/icons-material/Visibility';
import VisibilityOff from '@mui/icons-material/VisibilityOff';
import Textfield from '../globals/Textfield'
import { InputLabel, MenuItem, Select, FormHelperText, OutlinedInput } from '@mui/material'


const Password = (props) => { 
    return (
        <Textfield
            id={props.id}
            name={props.name}
            variant="filled"
            label={props.label}
            margin="normal"
            type={props.showPassword ? 'text' : 'password'}
            value={props.password}
            required
            fullWidth
            
            onChange={props.handlePsswd}
            sx={{...props.classes.multilineColor}}
            InputProps={{
                sx: {...props.classes.input,...props.classes.multilineColor},
                disableUnderline:true,
                endAdornment: (
                    <InputAdornment position="end">
                        <IconButton
                            aria-label="toggle password visibility"
                            onClick={props.handleClickShowPassword}
                            onMouseDown={props.handleMouseDownPassword}
                            edge="end"
                            >
                            {props.showPassword ? <VisibilityOff /> : <Visibility />}
                        </IconButton>
                    </InputAdornment>
                )
            }}
        />
    );
}



const Signup = ({
        auth,
        role,
        values,
        classes,
        pickerVal,
        handleValues,
        handleSelect,
        handleClickShowPassword,
        handleMouseDownPassword,
        handleShowConfirmPass,
        handleMouseConfirmPass,
    }) => {

    return (
        <Box pt={6}>
            <Typography component="h2" variant="subtitle2">
                Sign Up as TUP-T {role}
            </Typography>
            <FormControl fullWidth size="medium" sx={{pt:3}}>
                {
                    auth === 'register' &&                
                    <Grid container spacing={2} >
                        <Grid item lg={12} md={12} sm={12} xs={12}>
                            <FormControl variant="filled" fullWidth sx={{ minWidth: 'calc(100%)', maxWidth:100}}>
                                <InputLabel id="select">{role == 'supervisor' ? 'Companies' : 'Courses'}</InputLabel>
                                <Select
                                    type="select"
                                    labelId="select"
                                    id="pickerSelected"
                                    value={values.selectedPicker}
                                    variant="filled"
                                    onChange={handleSelect}
                                    sx={{...classes.multilineColor}}
                                    inputProps={{
                                        sx: {...classes.input,...classes.multilineColor},
                                        value:values.selectedPicker
                                    }}
                                    disableUnderline
                                >
                                    {
                                        console.log(values.selectedPicker)
                                    }
                                <MenuItem disabled value=''>Course</MenuItem>
                                {
                                    pickerVal.data.map((mi) => {
                                       return(role == 'supervisor' ?
                                            <MenuItem value={mi.company_id} key={mi.company_name}>{mi.company_name}</MenuItem> :
                                            <MenuItem value={mi.course_id} key={mi.course_name}>{mi.course_name} {mi.course_description}</MenuItem> )
                                    })
                                }                                
                                </Select>
                            </FormControl>
                        </Grid>
                        <Grid item lg={6} xs={6}>
                            <Textfield
                                id="firstname"
                                name="firstname"
                                label="First Name"
                                variant="filled" 
                                margin="normal"
                                required
                                fullWidth
                                value={values.firstname}
                                onChange={handleValues}
                                sx={{...classes.multilineColor}}
                                autoComplete="studentid"
                                InputProps={{
                                    sx: {...classes.input,...classes.multilineColor},
                                    disableUnderline:true
                                }}
                            />
                        </Grid>
                        <Grid item lg={6} xs={6}>
                            <Textfield
                                id="lastname"
                                name="lastname"
                                label="Last Name"
                                variant="filled" 
                                margin="normal"
                                required
                                fullWidth
                                value={values.lastname}
                                onChange={handleValues}
                                sx={{...classes.multilineColor}}
                                autoComplete="studentid"
                                InputProps={{
                                    sx: {...classes.input,...classes.multilineColor},
                                    disableUnderline:true
                                }}
                            />
                        </Grid>
                    </Grid>
                }

                {
                    role === 'student' && auth === 'register' &&

                        <Textfield
                            id="studentId"
                            name="studentId"
                            label="TUPT-XX-XXXX"
                            variant="filled" 
                            margin="normal"
                            required
                            fullWidth
                            value={values.studentId}
                            onChange={handleValues}
                            sx={{...classes.multilineColor}}
                            autoComplete="studentid"
                            InputProps={{
                                sx: {...classes.input,...classes.multilineColor},
                                disableUnderline:true
                            }}
                        />
                }

                        <Textfield
                            id="email"
                            name="email"
                            label="Email Address"
                            variant="filled" 
                            margin="normal"
                            required
                            fullWidth
                            value={values.email}
                            onChange={handleValues}
                            sx={{...classes.multilineColor}}
                            autoComplete="email"
                            InputProps={{
                                sx: {...classes.input,...classes.multilineColor},
                                disableUnderline:true
                            }}
                        />
                {
                    auth === 'login' &&
                        <Password
                            id="password"
                            name="password"
                            label="Password"
                            password={values.password}
                            showPassword={values.showPassword}
                            classes={classes}
                            handlePsswd={handleValues}
                            handleClickShowPassword={handleClickShowPassword}
                            handleMouseDownPassword={handleMouseDownPassword}
                        />
                }
                {
                auth === 'register' && 
                <Grid container spacing={{ lg: 2, md: 2 , sm: 0, xs: 0 }} >
                    <Grid item lg={6} md={6} sm={12} xs={12}>
                        <Password
                            id="password"
                            name="password"
                            label="Password"
                            password={values.password}
                            showPassword={values.showPassword}
                            classes={classes}
                            handlePsswd={handleValues}
                            handleClickShowPassword={handleClickShowPassword}
                            handleMouseDownPassword={handleMouseDownPassword}
                        />
                    </Grid>
                    <Grid item lg={6} md={6} sm={12} xs={12}>
                        <Password
                            id="confirmPassword"
                            name="confirmPassword"
                            label="Confirm Password"
                            password={values.confirmPassword}
                            showPassword={values.showConfirmPassword}
                            classes={classes}
                            handlePsswd={handleValues}
                            handleClickShowPassword={handleShowConfirmPass}
                            handleMouseDownPassword={handleMouseDownPassword}
                        />
                    </Grid>
                </Grid>
                }
            </FormControl>
        </Box>
    );
}
 
export default Signup;