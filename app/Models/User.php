<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;


/** @property string|null $avatar_path
 * @property string|null $avatar_url
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $is_active
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'is_active',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Get the public avatar URL from Supabase storage.
     */

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $path = $this->avatar_path;
                if (!$path) {
                    return null;
                }
                if (
                    str_starts_with($path, 'http://') ||
                    str_starts_with($path, 'https://')
                ) {
                    return $path;
                }
                try {
                    /** @var FilesystemAdapter $disk */
                    $disk = Storage::disk('supabase_avatar');
                    return $disk->url($path);
                } catch (\Throwable $e) {
                    return null;
                }
            },
        );
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
