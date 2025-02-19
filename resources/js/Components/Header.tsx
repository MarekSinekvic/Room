import { User } from "@/types";
import { Link, router } from "@inertiajs/react";
import { Avatar, Button, Divider, IconButton, InputAdornment, Menu, MenuItem, Stack, TextField } from "@mui/material";
import { useState } from "react";
import { BiSearch } from "react-icons/bi";
import { CiLogout } from "react-icons/ci";
import { IoSpeedometerOutline } from "react-icons/io5";
import { RxAvatar } from "react-icons/rx";
import { TfiWrite } from "react-icons/tfi";
import { VscAccount } from "react-icons/vsc";

type Auth = {
    user:User
}
export default function ({user} : Auth) {
    const [profileMenuAnchor,setProfileMenuAnchor] = useState<HTMLElement|null>(null);
    return (
    <header className="my-3 mx-5">
        <Stack direction={'row'} gap={1} justifyContent={'space-between'} alignItems={'center'} width={'100%'}>
            <Stack direction={'row'} gap={3} width={'50%'}>
                <Stack direction={'row'} className='text-3xl'>
                    <Link href="/">TheRoom</Link>
                </Stack>
                <TextField fullWidth size='small' slotProps={{input:{startAdornment:(<InputAdornment position='start'><BiSearch/></InputAdornment>)}}}/>
            </Stack>
            <Stack direction={'row'} gap={2}>
                {user ? (
                    <>
                <Stack direction={'row'} alignItems={'center'} gap={0.5}><Link href="/write" className="flex items-center"><TfiWrite className="mr-1"/> Write</Link></Stack>    
                        <Stack className="items-center relative">
                            <IconButton size="small" className="p-0" onClick={(e)=>{setProfileMenuAnchor(e.currentTarget)}} >
                                <RxAvatar size={'36px'} style={{margin:'-4px'}}/>
                            </IconButton>
                            <div className="text-xs absolute" style={{top: '90%'}}>{user.name}</div>
                        </Stack>
                        <Menu
                            anchorEl={profileMenuAnchor}
                            open={Boolean(profileMenuAnchor)}
                            onClose={()=>{setProfileMenuAnchor(null)}}
                            
                            >
                            <MenuItem><Link href={route('profile.edit')}><Stack direction={'row'} alignItems={'center'} textAlign={'center'} gap={1}><VscAccount size={'16px'} />Profile</Stack></Link></MenuItem>
                            <MenuItem><Link href={route('dashboard')}><Stack direction={'row'} alignItems={'center'} textAlign={'center'} gap={1}><IoSpeedometerOutline size={'16px'} />Dashboard</Stack></Link></MenuItem>
                            <Divider/>
                            <MenuItem><div onClick={()=>{router.post('/logout')}}><Stack direction={'row'} alignItems={'center'} textAlign={'center'} gap={1}><CiLogout size={'16px'} />Logout</Stack></div></MenuItem>
                        </Menu>
                    </>
                ) : (
                    <Stack direction={'row'} gap={0.5}>
                        <Link href={route('login')}> Log in</Link>
                        <Divider orientation='vertical'/>
                        <Link href={route('register')}>Register</Link>
                    </Stack>
                )}
            </Stack>
        </Stack>
    </header>);
}